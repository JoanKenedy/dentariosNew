import { Component, OnInit } from '@angular/core';
import { CourseService } from '../../service/course.service';
import { ActivatedRoute } from '@angular/router';
import { Toaster } from 'ngx-toast-notifications';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { SectionEditComponent } from '../section-edit/section-edit.component';
import { SectionDeleteComponent } from '../section-delete/section-delete.component';

@Component({
  selector: 'app-section-add',
  templateUrl: './section-add.component.html',
  styleUrls: ['./section-add.component.scss'],
})
export class SectionAddComponent implements OnInit {
  course_id: any;
  isLoading: any;
  title: any;
  SECTIONS: any = [];
  constructor(
    public courseService: CourseService,
    public activedRouter: ActivatedRoute,
    public toaster: Toaster,
    public modalService: NgbModal
  ) {}

  ngOnInit(): void {
    this.isLoading = this.courseService.isLoading$;
    this.activedRouter.params.subscribe((resp: any) => {
      console.log(resp);
      this.course_id = resp.id;
    });
    this.courseService.lisSections(this.course_id).subscribe((resp: any) => {
      console.log(resp);
      this.SECTIONS = resp.sections;
    });
  }
  editSection(SECTION: any) {
    const modalRef = this.modalService.open(SectionEditComponent, {
      centered: true,
      size: 'md',
    });
    modalRef.componentInstance.section_selected = SECTION;

    modalRef.componentInstance.SectionE.subscribe((newSection: any) => {
      let INDEX = this.SECTIONS.findIndex(
        (item: any) => item.id == newSection.id
      );
      if (INDEX != -1) {
        this.SECTIONS[INDEX] = newSection;
      }
    });
  }
  deleteSection(SECTION: any) {
    const modalRef = this.modalService.open(SectionDeleteComponent, {
      centered: true,
      size: 'md',
    });
    modalRef.componentInstance.section_selected = SECTION;

    modalRef.componentInstance.SectionD.subscribe((newSection: any) => {
      let INDEX = this.SECTIONS.findIndex((item: any) => item.id == SECTION.id);
      if (INDEX != -1) {
        this.SECTIONS.splice(INDEX, 1);
      }
    });
  }
  save() {
    if (!this.title) {
      this.toaster.open({
        text: 'NECESITAS INGRESAR UN NOMBRE DE SECCIÓN',
        caption: 'VALIDACION',
        type: 'danger',
      });
      return;
    }
    let data = {
      name: this.title,
      course_id: this.course_id,
      state: 1,
    };
    this.courseService.registerSection(data).subscribe((resp: any) => {
      console.log(resp);
      this.title = null;
      this.SECTIONS.push(resp.section);
      this.toaster.open({
        text: 'LA SECCIÓN SE REGISTRO CORRECTAMENTE',
        caption: 'SUCCESS',
        type: 'primary',
      });
    });
  }
}
