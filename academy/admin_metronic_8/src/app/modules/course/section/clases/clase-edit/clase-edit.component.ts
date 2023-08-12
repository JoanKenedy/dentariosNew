import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { CourseService } from '../../../service/course.service';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { Toaster } from 'ngx-toast-notifications';
import { DomSanitizer } from '@angular/platform-browser';

@Component({
  selector: 'app-clase-edit',
  templateUrl: './clase-edit.component.html',
  styleUrls: ['./clase-edit.component.scss'],
})
export class ClaseEditComponent implements OnInit {
  @Input() clase_selected: any;
  @Output() ClaseE: EventEmitter<any> = new EventEmitter();
  title: any;
  description: any;
  isLoading: any;
  FILES: any = [];

  FILES_CLASE: any = [];
  video_curso: any;
  isUploadVideo: Boolean = false;
  link_video_course: any = null;
  constructor(
    public courseService: CourseService,
    public modal: NgbActiveModal,
    public toaster: Toaster,
    public sanitizer: DomSanitizer
  ) {}

  ngOnInit(): void {
    this.isLoading = this.courseService.isLoading$;
    this.title = this.clase_selected.name;
    this.description = this.clase_selected.description;
    this.FILES_CLASE = this.clase_selected.files;
  }

  save() {
    let data = {
      name: this.title,
      description: this.description,
    };
    this.courseService
      .updateClase(data, this.clase_selected.id)
      .subscribe((resp: any) => {
        this.toaster.open({
          text: 'SE HA REGISTRADO LOS CAMBIOS DE LA CLASE',
          caption: 'SUCCESS',
          type: 'primary',
        });
        this.modal.close();
        this.ClaseE.emit(resp.clase);
      });
  }

  public onChange(event: any) {
    this.description = event.editor.getData();
  }

  uploadVideo() {
    let formData = new FormData();
    formData.append('video', this.video_curso);
    console.log(this.video_curso);
    this.isUploadVideo = true;
    this.courseService
      .uploadVideoClase(formData, this.clase_selected.id)
      .subscribe((resp: any) => {
        this.isUploadVideo = false;
        console.log(resp);
        this.link_video_course = resp.link_video;
      });
  }
  urlVideo() {
    return this.sanitizer.bypassSecurityTrustResourceUrl(
      this.link_video_course
    );
  }
  processVideo($event: any) {
    console.log($event.target.files[0].type);
    if ($event.target.files[0].type.indexOf('video') < 0) {
      this.toaster.open({
        text: 'Solo se aceptan videos',
        caption: 'Mensaje de validación',
        type: 'danger',
      });
      return;
    }

    this.video_curso = $event.target.files[0];
  }

  processFile($event: any) {
    for (const file of $event.target.files) {
      this.FILES.push(file);
    }

    console.log(this.FILES);
  }
  deleteFile(FILE: any) {}
}
